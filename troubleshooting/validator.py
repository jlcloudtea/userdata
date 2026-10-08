#!/usr/bin/env python3
"""Read-only check of a fixed CloudFormation stack; never takes credentials from browser."""
import json, os
from datetime import datetime, timezone

LABELS=["HTTP security group","Public subnet routing","Auto Scaling capacity","Scheduled scale-up (09:00 UTC)","Scheduled scale-down (11:00 UTC)"]

def main():
    checks=[{"name":n,"status":"Unavailable"} for n in LABELS]
    try:
        import boto3
        region=os.getenv("AWS_REGION","us-east-1")
        stack=os.getenv("LAB_STACK_NAME","troubleshoot")
        cf=boto3.client("cloudformation",region_name=region)
        def resource(name):
            return cf.describe_stack_resource(StackName=stack,LogicalResourceId=name)["StackResourceDetail"]["PhysicalResourceId"]
        sg_id,rt_id,igw_id,asg_name=(resource(x) for x in ["InstanceSecurityGroup","PublicRouteTable","InternetGateway","AutoScalingGroup"])
        subnet_ids={resource("PublicSubnet1"),resource("PublicSubnet2")}
        ec2=boto3.client("ec2",region_name=region)
        sg=ec2.describe_security_groups(GroupIds=[sg_id])["SecurityGroups"][0]
        def allows_http(rule):
            valid_protocol=rule.get("IpProtocol")=="-1" or (rule.get("IpProtocol")=="tcp" and rule.get("FromPort",65536)<=80<=rule.get("ToPort",-1))
            return valid_protocol and any(x.get("CidrIp")=="0.0.0.0/0" for x in rule.get("IpRanges",[]))
        http_ok=any(allows_http(r) for r in sg["IpPermissions"])
        rt=ec2.describe_route_tables(RouteTableIds=[rt_id])["RouteTables"][0]
        route_ok=any(r.get("DestinationCidrBlock")=="0.0.0.0/0" and r.get("GatewayId")==igw_id and r.get("State")=="active" for r in rt.get("Routes",[]))
        associated={x.get("SubnetId") for x in rt.get("Associations",[])}
        asg=boto3.client("autoscaling",region_name=region)
        group=asg.describe_auto_scaling_groups(AutoScalingGroupNames=[asg_name])["AutoScalingGroups"][0]
        hour=datetime.now(timezone.utc).hour
        expected=(2,3,4) if 9<=hour<11 else (1,2,3)
        capacity_ok=(group["MinSize"],group["DesiredCapacity"],group["MaxSize"])==expected
        actions=asg.describe_scheduled_actions(AutoScalingGroupName=asg_name)["ScheduledUpdateGroupActions"]
        def scheduled(cron,sizes):
            return any(" ".join((a.get("Recurrence") or "").split())==cron and a.get("TimeZone","UTC")=="UTC" and (a.get("MinSize"),a.get("DesiredCapacity"),a.get("MaxSize"))==sizes for a in actions)
        statuses=[http_ok,route_ok and subnet_ids.issubset(associated),capacity_ok,scheduled("0 9 * * *",(2,3,4)),scheduled("0 11 * * *",(1,2,3))]
        for check,passed in zip(checks,statuses): check["status"]="Pass" if passed else "Fail"
        print(json.dumps({"validation_available":True,"checks":checks}))
    except Exception:
        print(json.dumps({"validation_available":False,"checks":checks}))

if __name__=="__main__": main()
